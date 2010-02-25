<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php 

	/*
	 * Outil de cache ˆ deux niveaux :
	 * - utilisant les ŽlŽments static de PHP, permettant de s'assurer qu'une requete n'est pas exŽcutŽe 2 fois sur une meme page
	 * - utilisant MEMCACHED
	 * 
	 * Configuration du niveau : attribut LEVEL
	 * - LEVEL=0 : pas de cache
	 * - LEVEL=1 : cache uniquement en memoire
	 * - LEVEL=2 : cache en memoire et via memcached
	 * 
	 * fonctionne comme un Singleton :
	 * $cache=CacheBase::getCache();
	 * 
	 * puis :
	 * $cache->set("cle","valeur");
	 * $valeur=$cache->get("cle");
	 * 
	 * Pour mettre en cache le rŽsultat d'un SELECT :
	 * $cache->mysql_query("requete",$link)
	 * 
	 * Pour mettre en cache le rŽsultat d'un COUNT SQL :
	 * $cache->mysql_query_count("requete",$link)
	 * 
	 * Pour changer temporairement le LEVEL
	 * $cache->switchLevel(x)  -> la prochaine opŽration (mysql_query...) se fera avec ce level temporaire
	 * 
	 */
	class CacheBase
	{
		public static $AGE=30; // 
		public static $LEVEL=1;
		
		
		
		private $levelhistory;		
		private $result_cache = array();
		// singleton
		private static $cache=null;
		private function CacheBase()
		{
			$this->levelhistory=CacheBase::$LEVEL;
		}

		public static function getCache()
		{
			if(!CacheBase::$cache)
				CacheBase::$cache=new CacheBase();
			return CacheBase::$cache;
		}

		public function switchLevel($level)
		{
			$this->levelhistory=CacheBase::$LEVEL;
			CacheBase::$LEVEL=$level;
		}
		private function switchBackLevel()
		{
			CacheBase::$LEVEL=$this->levelhistory;
		}
		
		private function getMemcache()
		{
			if(CacheBase::$LEVEL!=2)
				return null;
			$memcache = new Memcache(); 
				//$memcache->addServer('memcache_host', 11211); 
				//$memcache->addServer('memcache_host2', 11211);
			return $memcache;
		}

		private function setCache2($key,$value)
		{
			if(CacheBase::$LEVEL!=2) return FALSE;
			$this->getMemcache()->set($key,$value, false, CacheBase::$AGE);
		}
		
		private function getCache2($key)
		{
			if(CacheBase::$LEVEL!=2) return FALSE;
			return $this->getMemcache()->get($key);
		}
		
		public function get($key)
		{
			if(CacheBase::$LEVEL==0) return FALSE;
			
			$hash = hash('md5',$key);
			$retour=$this->result_cache[$hash];
		    if (!$retour) // ce n'est pas dans le niveau 1
            {
 	           	$retour=$this->getCache2($key);
	           	if($retour==FALSE) // ce n'est pas dans le niveau 2
            		return FALSE;
            }
            return $retour;
		}
		public function set($key,$value)
		{
			if(CacheBase::$LEVEL==0) return;
			
		    $hash = hash('md5', $key);
			$this->result_cache[$hash]=$value;
			$this->setCache2($key,$value);			
		}
		
		public function mysql_query($query,$link)
		{
         	$data=$this->get($query);
            if (!$data)
            {
            	if($link==null)
					$resul=mysql_query($query);
				else
					$resul=mysql_query($query,$link);
				
				while($row = mysql_fetch_object($resul))
				{
					$data[]=$row;
				}		
				$this->set($query,$data);
            }
            $this->switchBackLevel();
            return $data;
		}

		public function mysql_query_count($query,$link)
		{
			$num=$this->get($query);
            if ($num<0)
            {
				$resul=mysql_query($query,$link);
				$num=mysql_num_rows($resul);		
				
				$this->set($query,$num);
            }
            $this->switchBackLevel();
            return $num;
		}
	}
?>